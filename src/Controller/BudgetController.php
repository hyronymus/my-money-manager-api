<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\IncomeStream;
use App\Entity\Expense;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BudgetController
 * @package App\Controller
 */
class BudgetController extends Controller
{

    /**
     * @Route("/budget/{id}/expense", name="save_expense")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function saveExpenseAction($id,Request $request)
    {
        $content = $request->getContent();
        if(!empty($content)){
            $postBody = json_decode($content,true);
            $budgets = $this->getDoctrine()->getRepository(Budget::class);

            /** @var Budget $budget*/
            $budget = $budgets->find($postBody['budgetId']);

            $expense = new Expense(
                $postBody['name'],
                $postBody['amount'],
                $budget
            );

            $em = $this->getDoctrine()->getManager();

            $em->persist($expense);

            $em->flush();

            return new JsonResponse([
                "status" => "success",
                "message" => 'Saved new expense with id '.$expense->getId(),
            ]);
        }
        else {
            return new JsonResponse([
                "status" => "failure",
                "message" => "No request body"
            ]);
        }
    }

    /**
     * @Route("/budget/{id}/incomeStream", name="save_income_stream")
     * @Method({"POST"})
     */
    public function saveIncomeStreamAction($id,Request $request)
    {
        $content = $request->getContent();
        if(!empty($content)) {
            $postBody = json_decode($request->getContent(), true);
            $budgets = $this->getDoctrine()->getRepository(Budget::class);

            /** @var Budget $budget */
            $budget = $budgets->find($postBody['budgetId']);

            $incomeStream = new IncomeStream(
                $postBody['name'],
                $postBody['amount'],
                $postBody['frequency'],
                $budget
            );


            // you can fetch the EntityManager via $this->getDoctrine()
            // or you can add an argument to your action: index(EntityManagerInterface $em)
            $em = $this->getDoctrine()->getManager();

            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($incomeStream);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new JsonResponse([
             "status" => "failure",
             "message" => 'No Request Body',
            ]);
        }
        else {
            return new JsonResponse([
                "status" => "success",
                "message" => 'Saved new income stream with id ' . $incomeStream->getId(),
            ]);
        }
    }

    /**
     * @Route("/budget", name="save_budget")
     * @Method({"POST"})
     */
    public function saveBudgetAction(Request $request)
    {
            $postBody = json_decode($request->getContent(),true);

            $budget = new Budget();

            $incomeStreams = array_map(function ($incomeStream) use ($budget) {
                return new IncomeStream(
                    $incomeStream["name"],
                    $incomeStream["amount"],
                    $incomeStream["frequency"],
                    $budget
                );
            }, $postBody['incomeStreams']);

            $expenses = array_map(function ($expense) use ($budget) {
                return new Expense(
                    $expense["name"],
                    $expense["amount"],
                    $budget
                );
            }, $postBody['expenses']);

            $budget->setIncomeStreams($incomeStreams);
            $budget->setExpenses($expenses);

            // you can fetch the EntityManager via $this->getDoctrine()
            // or you can add an argument to your action: index(EntityManagerInterface $em)
            $em = $this->getDoctrine()->getManager();

            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($budget);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return new Response('Saved new product with id '.$budget->getId());

    }

    /**
     * @Route("/budget/{id}", name="get_budgets", methods="GET")
     */
    public function getBudgetsAction($id)
    {
        $idToPass = null;
        if (isset($id)) {
            $idToPass = $id;
        } else {
            $idToPass = 4;
        }
        $budgets = $this->getDoctrine()->getRepository(Budget::class);

        /** @var Budget $budget*/
        $budget = $budgets->find($idToPass);

        $incomeStreams = array_map(function(IncomeStream $incomeStream) {
            return [
                "key" => $incomeStream->getId(),
                "name" => $incomeStream->getName(),
                "amount" => $incomeStream->getAmount(),
                "frequency" => $incomeStream->getFrequency(),
            ];
        }, $budget->getIncomeStreams());

        $expenses = array_map(function(Expense $expense) {
            return [
                "key" => $expense->getId(),
                "name" => $expense->getName(),
                "amount" => $expense->getAmount(),
            ];
        }, $budget->getExpenses());

        return new JsonResponse([
            "incomeStreams" => $incomeStreams,
            "expenses" => $expenses,
        ]);
    }
}