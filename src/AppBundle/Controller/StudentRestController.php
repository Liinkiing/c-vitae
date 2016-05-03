<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StudentRestController extends Controller
{
    /**
     * @return \AppBundle\Entity\Student[]|array
     * @QueryParam(name="group", default="A,B,C,D")
     * @QueryParam(name="name", requirements="[a-z]+")
     * @QueryParam(name="age", requirements="\d+")
     * @QueryParam(name="role", requirements="[a-z]+")
     * @QueryParam(name="bac")
     * @QueryParam(name="sort", requirements="(asc|desc|ASC|DESC)", default="asc")
     */
    public function getStudentsAction(ParamFetcher $paramFetcher){

        $group = ($paramFetcher->get('group') == '') ? ['A', 'B', 'C', 'D'] : explode(',', $paramFetcher->get('group'));
        $name = $paramFetcher->get('name');
        $age = $paramFetcher->get('age');
        $role = $paramFetcher->get('role');
        $bac = $paramFetcher->get('bac');
        $sort = $paramFetcher->get('sort');

        $students = $this->getDoctrine()->getRepository('AppBundle:Student')->findWithParams($group, $name, $age, $role, $bac, $sort);
        return $students;

    }

    /**
     * @QueryParam(name="name")
     * @QueryParam(name="age", requirements="\d+")
     * @QueryParam(name="role", requirements="[a-z]+")
     * @QueryParam(name="bac")
     */
    public function getGroupsStudentsAction(ParamFetcher $paramFetcher){

        $name = $paramFetcher->get('name');
        $age = $paramFetcher->get('age');
        $role = $paramFetcher->get('role');
        $bac = $paramFetcher->get('bac');

        $final = ["A" =>
            ["name" => "Groupe A",
                "results" => $this->getDoctrine()->getRepository('AppBundle:Student')->findWithParams('A', $name, $age, $role, $bac)],
            "B" =>
                ["name" => "Groupe B",
                    "results" => $this->getDoctrine()->getRepository('AppBundle:Student')->findWithParams('B', $name, $age, $role, $bac)],
            "C" =>
                ["name" => "Groupe C",
                    "results" => $this->getDoctrine()->getRepository('AppBundle:Student')->findWithParams('C', $name, $age, $role, $bac)],
            "D" =>
                ["name" => "Groupe D",
                    "results" => $this->getDoctrine()->getRepository('AppBundle:Student')->findWithParams('D', $name, $age, $role, $bac)]];

        return ["results" => $final];
    }

}
