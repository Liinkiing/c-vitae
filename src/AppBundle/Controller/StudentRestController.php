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
     * @QueryParam(name="name")
     * @QueryParam(name="age", requirements="\d+")
     * @QueryParam(name="role")
     * @QueryParam(name="bac")
     * @QueryParam(name="prog_lang")
     * @QueryParam(name="gender")
     * @QueryParam(name="linkedin", requirements="(true|false|TRUE|FALSE)", strict=true, nullable=true)
     * @QueryParam(name="viadeo", requirements="(true|false|TRUE|FALSE)", strict=true, nullable=true)
     * @QueryParam(name="langs")
     * @QueryParam(name="sort", requirements="(asc|desc|ASC|DESC)", strict=true, nullable=true, default="asc")
     * @QueryParam(name="by")

     */
    public function getStudentsAction(ParamFetcher $paramFetcher){

        $group = ($paramFetcher->get('group') == '') ? ['A', 'B', 'C', 'D'] : explode(',', $paramFetcher->get('group'));

        self::fillParams($paramFetcher, $name, $age, $role, $bac, $progLang, $gender, $linkedin, $viadeo, $langs, $sort, $by);

        $students = $this->getDoctrine()->getRepository('AppBundle:Student')->findWithParams($group, $name, $age, $role, $bac, $progLang,
            $gender, $linkedin, $viadeo, $langs, $sort, $by);
        return $students;

    }

    /**
     * @QueryParam(name="name")
     * @QueryParam(name="age", requirements="\d+")
     * @QueryParam(name="role")
     * @QueryParam(name="bac")
     * @QueryParam(name="prog_lang")
     * @QueryParam(name="gender")
     * @QueryParam(name="linkedin", requirements="(true|false|TRUE|FALSE)", strict=true, nullable=true)
     * @QueryParam(name="viadeo", requirements="(true|false|TRUE|FALSE)", strict=true, nullable=true)
     * @QueryParam(name="langs")
     * @QueryParam(name="sort", requirements="(asc|desc|ASC|DESC)", strict=true, nullable=true, default="asc")
     * @QueryParam(name="by")
     */
    public function getGroupsStudentsAction(ParamFetcher $paramFetcher){

        self::fillParams($paramFetcher, $name, $age, $role, $bac, $progLang, $gender, $linkedin, $viadeo, $langs, $sort, $by);

        $final= [];
        for($group = "A"; $group <= "D"; $group++){
            $final[$group] = ["name"    => "Groupe $group",
                              "results" => $this->getDoctrine()->getRepository('AppBundle:Student')->findWithParams($group, $name, $age, $role, $bac, $progLang,
                                  $gender, $linkedin, $viadeo, $langs, $sort, $by)];
        }

        return ["results" => $final];
    }

    public static function fillParams(ParamFetcher $paramFetcher, &$name, &$age, &$role, &$bac, &$progLang, &$gender, &$linkedin, &$viadeo, &$langs, &$sort, &$by){
        $name = $paramFetcher->get('name');
        $age = $paramFetcher->get('age');
        $role = (explode(',', $paramFetcher->get('role'))[0] == '') ? null : explode(',', $paramFetcher->get('role'));
        $bac = (explode(',', $paramFetcher->get('bac'))[0] == '') ? null : explode(',', $paramFetcher->get('bac'));
        $progLang = (explode(',', $paramFetcher->get('prog_lang'))[0] == '') ? null : explode(',', $paramFetcher->get('prog_lang'));
        $gender = $paramFetcher->get('gender');
        $linkedin = $paramFetcher->get('linkedin');
        $viadeo = $paramFetcher->get('viadeo');
        $langs = (explode(',', $paramFetcher->get('langs'))[0] == '') ? null : explode(',', $paramFetcher->get('langs'));
        $sort = $paramFetcher->get('sort');
        $by = (explode(',', $paramFetcher->get('by'))[0] == '') ? null : explode(',', $paramFetcher->get('by'));
    }

}
