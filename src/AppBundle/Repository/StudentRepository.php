<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Student;

/**
 * StudentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StudentRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * @return Student[]
     */
    public function findByAlphabeticalOrder()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('student')
            ->from('AppBundle:Student', 'student')
            ->orderBy('student.lastName')
            ->getQuery()
            ->getResult();

    }

    public function findByTPGroup($tp)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('student')
            ->from('AppBundle:Student', 'student')
            ->where($qb->expr()->eq('student.groupeTp', '?1'))
            ->setParameter(1, $tp)
            ->getQuery()
            ->getResult();
    }

    public function findByFirstnameOrLastname($name)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('student')
            ->from('AppBundle:Student', 'student')
            ->where($qb->expr()->eq('student.firstName', '?1'))
            ->orWhere($qb->expr()->eq('student.lastName', '?1'))
            ->setParameter(1, $name)
            ->getQuery()
            ->getResult();
    }

    public function findWithParams($group = ['A', 'B', 'C', 'D'], $name = null, $age = null, $role = null, $bac = null, $programmingLanguages = null, $order = 'ASC')
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $result = $qb->select('student')
            ->from('AppBundle:Student', 'student')
            ->where($qb->expr()->in('student.groupeTp', '?1'))->setParameter(1, $group);
        if ($name != null) {
            $result->andWhere($qb->expr()->like(
                "CONCAT(student.firstName, ' ', student.lastName)",
                "?2"
            ))->setParameter(2, "%$name%");
        }
        if ($age != null) {
            $result->andWhere($qb->expr()->eq('student.age', '?3'))->setParameter(3, $age);
        }
        if ($role != null) {
            $result->andWhere($qb->expr()->in('student.projectRole', '?4'))->setParameter(4, $role);
        }
        if ($bac != null) {
            $result->andWhere($qb->expr()->in('student.bac', '?5'))->setParameter(5, $bac);
        }
        if ($programmingLanguages != null) {
            for ($i = 0; $i < count($programmingLanguages); $i++) {
                $parameterCount = $i + 6;
                $result->andWhere($qb->expr()->like('student.programmingLanguages', "?$parameterCount"))->setParameter($parameterCount, "%$programmingLanguages[$i]%");
            }
        }
        $result->orderBy('student.lastName', $order);
        return $result->getQuery()->getResult();

    }

    public function findProjectRoles()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $roles = $qb->select('student.projectRole')->distinct()
            ->from('AppBundle:Student', 'student')
            ->getQuery()
            ->getResult();
        $final = [];
        foreach ($roles as $role) {
            array_push($final, $role['projectRole']);
        }
        return $final;
    }

    /**
     * @return array
     */
    public function findProgrammingLanguages(){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $langs = $qb->select('student.programmingLanguages')->distinct()
            ->from('AppBundle:Student', 'student')
            ->getQuery()
            ->getScalarResult();
        $temp = [];
        foreach($langs as $lang){
            array_push($temp, $lang['programmingLanguages']);
        }
        $langs = [];
        foreach($temp as $item){
            foreach(explode(',', $item) as $language){
                if($language) array_push($langs, trim($language));
            }
        }
        return array_unique($langs);
    }

    public function findSecteurVise()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $secteurs = $qb->select('student.secteur')->distinct()
            ->from('AppBundle:Student', 'student')
            ->getQuery()
            ->getResult();
        $final = [];
        foreach ($secteurs as $secteur) {
            array_push($final, $secteur['secteur']);
        }
        return $final;
    }

    public function getMinOf($attribute){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $results = $qb->select('student.' . $attribute)->distinct()
            ->from('AppBundle:Student', 'student')
            ->getQuery()
            ->getResult();
        $final = [];
        foreach ($results as $result) {
            array_push($final, $result[$attribute]);
        }
        return min($final);
    }

    public function getMaxOf($attribute){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $results = $qb->select('student.' . $attribute)->distinct()
            ->from('AppBundle:Student', 'student')
            ->getQuery()
            ->getResult();
        $final = [];
        foreach ($results as $result) {
            array_push($final, $result[$attribute]);
        }
        return max($final);
    }

    public function findByGroups()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('student')
            ->from('AppBundle:Student', 'student')
            ->groupBy('student.groupeTp')
            ->getQuery()->getResult();
    }
}
