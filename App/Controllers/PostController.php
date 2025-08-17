<?php

namespace App\Controllers;

use App\Model\Post;
use App\Repository\GroupRepository;
use App\Repository\PersonRepository;
use App\Repository\PostRepository;
use App\Validation\Validator;
use Core\Attribute\Route;
use Core\Controller;
use Core\Request;
use DateTime;
use Exception;

class PostController extends Controller {
    const DEFAULT_ITEMS_PER_PAGE = 10;

    private PostRepository $postRepository;
    private PersonRepository $personRepository;
    private GroupRepository $groupRepository;
    private Validator $validator;

    public function __construct() {
        $this->personRepository = new PersonRepository();
        $this->postRepository = new PostRepository();
        $this->groupRepository = new GroupRepository();
        $this->validator = new Validator();
    }

    #[Route('/posts', 'GET')]
    public function index(Request $request) {
        $groupId  = $request->getIntQueryParam('group_id', null, true);
        $page     = $request->getIntQueryParam('page', 1, true);
        $perPage  = $request->getIntQueryParam('per_page', self::DEFAULT_ITEMS_PER_PAGE, true);
        $fromDate = $request->getDateQueryParam('from_date');
        $toDate   = $request->getDateQueryParam('to_date'); 

        $totalPosts = $this->postRepository->countByGroupAndDateRange(
            groupId: $groupId,
            fromDate: $fromDate,
            toDate: $toDate,
        );

        $totalPages = ceil($totalPosts / $perPage);

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $posts = $this->postRepository->findByGroupAndDateRange(
            groupId: $groupId,
            limit: $perPage,
            offset: ($page - 1) * $perPage,
            fromDate: $fromDate,
            toDate: $toDate,
        );

        $persons = $this->personRepository->findAll();
        $groups = $this->groupRepository->findAll();

        // Ajax request is used to update table contents and pagination
        if ($this->isAjaxRequest()) {
            $tableContent = $this->renderPartial('components/table', [
                'posts' => $posts,
                'persons' => $persons,
                'groups' => $groups,
                'currentGroupId' => $groupId,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'perPage' => $perPage
            ]);

            $pagination = $this->renderPartial('components/pagination', [
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'perPage' => $perPage,
                'totalPosts' => $totalPosts
            ]);

            $this->sendJsonResponse(200, [
                'table' => $tableContent,
                'pagination' => $pagination,
            ]);

            return;
        }

        $this->render('pages/posts/index', [
            'posts' => $posts,
            'persons' => $persons,
            'groups' => $groups,
            'currentGroupId' => $groupId,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
            'totalPosts' => $totalPosts
        ]);
    }

    #[Route('/posts/{id}', 'GET')]
    public function show($id) {
        $post = $this->postRepository->findOneBy(['id' => $id]);
        
        if (!$post) {
            $this->sendJsonResponse(404, ['error' => 'Post not found']);
            return;
        }
        
        $this->sendJsonResponse(200, $post->toArray());
    }

    #[Route('/posts', 'POST')]
    public function store() {
        try {
            $data = $this->getRequestData();
            $post = new Post(
                0,
                $data['person_base_id'] ? $data['person_base_id'] : null,
                $data['content'] ?? '',
                $data['created_at'] ? new DateTime($data['created_at']) : null
            );
            
            $errors = $this->validator->validate($post);

            if (!empty($errors)) {
                $this->sendJsonResponse(422, [
                    'success' => false,
                    'errors' => $errors,
                    'message' => 'Validation failed'
                ]);
                return;
            }

            $this->postRepository->save($post);

            $this->sendJsonResponse(200, [
                'success' => true,
            ]);
            return;

        } catch (Exception $e) {
            $this->sendJsonResponse(400, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    #[Route('/posts/{id}', 'PUT')]
    public function update(Request $request) {
        try {
            /** @var Post $post */
            $post = $this->postRepository->findOneBy(['id' => $request->get('id') ?? null]);
            
            if (!$post) {
                $this->sendJsonResponse(404, ['error' => 'Post not found']);
                return;
            }
            
            $post->setPersonBaseId($request->get('person_base_id') ? $request->get('person_base_id') : null);
            $post->setContent($request->get('content') ? $request->get('content') : null);
            $post->setCreatedAt($request->get('created_at') ? new DateTime($request->get('created_at')) : null);
                        
            $errors = $this->validator->validate($post);

            if (!empty($errors)) {
                $this->sendJsonResponse(422, [
                    'success' => false,
                    'errors' => $errors,
                    'message' => 'Validation failed'
                ]);
                return;
            }

            $updatedPost = $this->postRepository->save($post);
            
            $this->sendJsonResponse(200, [
                'success' => true,
                'post' => $updatedPost->toArray(),
                'message' => 'Post updated successfully'
            ]);
        } catch (Exception $e) {
            $this->sendJsonResponse(400, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    #[Route('/posts/{id}', 'DELETE')]
    public function delete($id) {
        $success = $this->postRepository->delete($this->postRepository->findOneBy(['id' => $id]));
        
        if ($success) {
            $this->sendJsonResponse(200, ['success' => true, 'message' => 'Post deleted successfully']);
            return;
        }
        
        $this->sendJsonResponse(500, ['success' => false, 'message' => 'Failed to delete post']);
    }
}
