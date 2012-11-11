<?php

class Resource_Comment_List_Resource_Mysql extends Pimcore_Model_List_Resource_Mysql_Abstract
{
    /**
     * Loads a list of objects for the specicifies parameters,
     * returns an array of Object_Abstract elements.
     *
     * @return array
     */
    public function load()
    {
        $comments = array();
        $commentsData = $this->db->fetchAll(sprintf(
            "SELECT id FROM plugin_commenting_comments%s%s%s",
            $this->getCondition(),
            $this->getOrder(),
            $this->getOffsetLimit()
        ), $this->model->getConditionVariables());

        foreach ($commentsData as $commentData) {
            // return all comments as Type Commenting
            $comments[] = Resource_Comment::getById($commentData["id"]);
        }

        $this->model->setComments($comments);
        return $comments;
    }

    public function getTotalCount()
    {
        $amount = $this->db->fetchRow(sprintf(
            "SELECT COUNT(*) as amount FROM plugin_commenting_comments%s",
            $this->getCondition()
        ), $this->model->getConditionVariables());

        return $amount["amount"];
    }

    public function getCount()
    {
        if (count($this->model->getObjects()) > 0) {
            return count($this->model->getObjects());
        }

        $amount = $this->db->fetchAll(sprintf(
            "SELECT COUNT(*) as amount FROM plugin_commenting_comments%s%s%s",
            $this->getCondition(),
            $this->getOrder(),
            $this->getOffsetLimit()
        ), $this->model->getConditionVariables());
        return $amount["amount"];
    }

}
