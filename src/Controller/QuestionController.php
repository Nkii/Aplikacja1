<?php
/**
 * Question controller.
 */

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class QuestionController.
 *
 * @Route("/question")
 */
class QuestionController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request $request        HTTP request
     * @param QuestionRepository $questionRepository Question repository
     * @param PaginatorInterface $paginator      Paginator
     *
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="question_index",
     * )
     */
    public function index(Request $request, QuestionRepository $questionRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $questionRepository->findAll(),
            $request->query->getInt('page', 1),
            QuestionRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'question/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Question $question Question entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="question_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Question $question): Response
    {
        return $this->render(
            'question/show.html.twig',
            ['question' => $question]
        );
    }
    /**
     * Create action.
     *
     * @param Request $request HTTP request
     * @param QuestionRepository $questionRepository Category repository
     *
     * @return Response HTTP Response
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="question_create",
     * )
     */
    public function create(Request $request, QuestionRepository $questionRepository): Response
    {
        $question= new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->getData();
            $questionRepository->add($question, true);

            $this->addFlash('success','message_created_successfully');
            return $this->redirectToRoute('question_index');

        }

        return $this->render(
            'question/create.html.twig',
            ['form'=> $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request
     * @param Question $question
     * @param QuestionRepository $questionRepository
     * @return Response
     *
     * @Route(
     *     "/{id}/edit",
     *     name="question_edit",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function edit(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        $form = $this->createForm(QuestionType::class, $question, ['method'=> 'PUT']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $form->getData();
            $questionRepository->add($question, true);

            $this->addFlash('success','message_updated_successfully');
            return $this->redirectToRoute('question_index');

        }
        return $this->render(
            'question/edit.html.twig',
            ['form'=> $form->createView(),
                'question'=>$question,
            ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request
     * @param Question $question
     * @param QuestionRepository $questionRepository
     * @return Response
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     name="question_delete",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function delete(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        if ($question->getAnswers()->count()){
            $this->addFlash('warning','message_category_contains_answers');

            return $this->redirectToRoute('question_index');
        }

        $form = $this->createForm(FormType::class, $question, ['method'=>'DELETE']);
        $form->handleRequest($request);

        if  ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if($form->isSubmitted() && $form->isValid()){
            $questionRepository->remove($question, true);

            $this->addFlash('success','message_deleted_successfully');

            return $this->redirectToRoute('question_index');
        }

        return $this->render(
            'question/delete.html.twig',
            [
                'form'=>$form->createView(),
                'question'=>$question,
            ]
        );
    }
}