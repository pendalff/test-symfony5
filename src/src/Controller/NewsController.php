<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\CommentType;
use App\Message\CreateNewsComment;
use App\Repository\CommentRepository;
use App\Twig\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\Repository\NewsRepository;

class NewsController extends AbstractController
{
    
    private NewsRepository $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }
    
    /**
     * @Route("/{page}", name="news_list", requirements={"page"="\d+"})
     * @Route("/category/{category}/{page}", name="news_category_list", requirements={"page"="\d+"})
     * @Template
     */
    public function listAction(int $page = 1, ?int $category = null, Request $request)
    {
//        $limit = $request->query->getInt('itemsPerPage', 3);
//
//        $sortDirections = ['ASC', 'DESC'];
//        $sort = $request->query->getAlpha('sortDirection', $sortDirections[0]);
//        if (!in_array($sort, $sortDirections)) {
//            $sort = $sortDirections[1];
//        }

        $pageTemplate = Page::factory([
            'currentPage'     => $page,
            'enteriesLimit' => $request->query->getInt('enteriesLimit', 3),
            'sortDirection'   => $request->query->getAlpha('sortDirection'),
            'sortField'       => 'createdAt',
        ]);

        try {
            $paginator = $this->newsRepository->findAllActiveByPage($pageTemplate, $category);
            $pageTemplate->setEnteries($paginator->getIterator());
        } catch (\OutOfBoundsException $e) {
            throw $this->createNotFoundException('No news here');
        }

        return [
            'page' => $pageTemplate,
            'categoryId' => $category
        ];
    }

    /**
     * @Route(path="/news/{slug}", name="news_show")
     * @Template
     */
    public function showAction(string $slug, Request $request)
    {
        $newsEntity = $this->newsRepository->findOneActiveBySlug($slug);

        $commentForm = $this->createCommentForm($slug, $newsEntity->getId());
        $commentForm->handleRequest($request);

        return [
            'page'          => Page::factory(),
            'newsEntity'    => $newsEntity,
            'commentForm'   => $commentForm->createView(),
        ];
    }

    /**
     * @Route(path="news/{slug}/create-comment", name="create_comment", methods={"POST"})
     */
    public function createComment(string $slug, Request $request): Response
    {
        $form = $this->createCommentForm($slug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dispatchMessage($form->getData());
            $this->addFlash('success', 'You comment added');

            return $this->redirectToRoute('news_show', ['slug' => $slug]);
        }

        return $this->forward(static::class . '::showAction', [
            'slug'      => $slug,
            'request'   => $request,
        ]);
    }

    protected function createCommentForm(string $slug, int $newsId = null): FormInterface
    {
        return $this->createForm(
            CommentType::class,
            $newsId ? (new CreateNewsComment())->setNewsId($newsId) : null,
            !$newsId ? [] : [
                'action' => $this->generateUrl('create_comment', ['slug' => $slug]),
                'method' => 'POST',
            ]
        );
    }
}
