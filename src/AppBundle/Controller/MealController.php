<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Meal;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Meal controller.
 *
 * @Route("/meal")
 *
 * @Security("has_role('ROLE_USER')")
 */
class MealController extends Controller
{
    /**
     * Lists all meal entities.
     *
     *
     * @Route("/", name="meal_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {

        $array = array();


        $user =$this->getUser();
        if ( $request->query->get('startdate')) {
      $array['start_date']=  $request->query->get('startdate');
        $array['end_date'] = $request->query->get('enddate');
        }
        $form = $this->createForm('AppBundle\Form\MealSearchType',$array);

        $em = $this->getDoctrine()->getManager();

        $endDate = new \DateTime();
        $startDate = new \DateTime('-7 days');


        $conn =$em->getConnection();
        $form->handleRequest($request);
        $res =null;

        if ($form->isSubmitted() && $form->isValid()) {

            $startDate= $form['start_date']->getData();
            $endDate = $form['end_date']->getData();
            $st= $startDate->format("Y-m-d");
            $en = $endDate->format("Y-m-d");

            $q = "SELECT meal_date as date , sum(calories) as calories from meal "
                . "WHERE meal_date >= '" . $st . "' "
                . "AND meal_date <= '" . $en . "' "
                . "and user_id =:user GROUP BY meal_date";
            $stmt = $conn->prepare($q);

            $stmt->bindValue('user', $user->getId());
            $stmt->execute();

            $res = $stmt->fetchAll();

            foreach ($res as &$r) {


                $arr = array(
                    'user' => $user,
                    'mealDate' => new \DateTime($r['date'])
                );
                $meals = $em->getRepository('AppBundle:Meal')->findBy($arr,array('mealDateTime'=> 'ASC'));
                $r['meals'] = $meals;
            }

        }


      //  $meals = $em->getRepository('AppBundle:Meal')->$startDate->fi);

        return $this->render('meal/index.html.twig', array(
            'meals_day' => $res,
            'form' => $form->createView()
        ));
    }


    /**
     * Creates a new meal entity.
     *
     * @Route("/new", name="meal_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user= $this->getUser();
        $meal = new Meal();
        $form = $this->createForm('AppBundle\Form\MealType', $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $meal->setUser($user);
            $meal->setMealDate($meal->getMealDateTime());
            $em->persist($meal);
            $em->flush();

            return $this->redirectToRoute('meal_edit', array('id' => $meal->getId()));
        }

        return $this->render('meal/new.html.twig', array(
            'meal' => $meal,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a meal entity.
     *
     * @Route("/{id}", name="meal_show")
     * @Method("GET")
     */
    public function showAction(Meal $meal)
    {
        $deleteForm = $this->createDeleteForm($meal);

        return $this->render('meal/show.html.twig', array(
            'meal' => $meal,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing meal entity.
     *
     * @Route("/{id}/edit", name="meal_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Meal $meal)
    {
        $user=$this->getUser();
        $muser= $meal->getUser();
        if ($muser != $user) {
            throw  new  AccessDeniedHttpException("Access Denied");
        }
        $deleteForm = $this->createDeleteForm($meal);
        $editForm = $this->createForm('AppBundle\Form\MealType', $meal);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('meal_edit', array('id' => $meal->getId()));
        }

        return $this->render('meal/edit.html.twig', array(
            'meal' => $meal,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a meal entity.
     *
     * @Route("/{id}", name="meal_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Meal $meal)
    {
        $user=$this->getUser();
        $muser= $meal->getUser();
        if ($muser != $user) {
            throw  new  AccessDeniedHttpException("Access Denied");
        }

        $form = $this->createDeleteForm($meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($meal);
            $em->flush();
        }

        return $this->redirectToRoute('meal_index');
    }

    /**
     * Creates a form to delete a meal entity.
     *
     * @param Meal $meal The meal entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Meal $meal)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('meal_delete', array('id' => $meal->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
