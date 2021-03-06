<?php

class Commenting_AdminController extends Pimcore_Controller_Action_Admin
{
    public function commentsAction()
    {
        if ($this->_getParam('xaction') == "destroy") {
            $id = $this->_getParam("comments");
            $id = str_replace('"', '', $id);
            Commenting_Plugin::deleteComment($id);
            $results["success"] = true;
            $results["comments"] = "";
        } else {

            $id = $this->_getParam("objectid");
            $type = $this->_getParam("type");
            if ($type == "object") {
                $target = Object_Abstract::getById($id);
            } else if ($type == "page" || $type == "snippet") {
                $target = Document::getById($id);
            } else {
                //try asset
                $target = Asset::getById($id);
            }


            $comments = Commenting_Plugin::getComments($target);

            $results = array();
            if (is_array($comments)) {
                foreach ($comments as $comment) {

                    $shorttext = $comment->getData();
                    if (strlen($shorttext) > 50) {
                        $shorttext = substr($shorttext, 0, 50) . "...";
                    }
                    $user = $comment->getUser();

                    $results["comments"][] = array(
                        "c_id" => $comment->getId(),
                        "c_shorttext" => $shorttext,
                        "c_text" => $comment->getData(),
                        "c_user" => ($user instanceof Object_Abstract)
                            ? $user->getFullPath()
                            : $comment->getMetadata()->name . ' (' . $comment->getMetadata()->email . ')',
                        "c_created" => $comment->getDate()->getTimestamp());
                }
            }

            if (!isset($results["comments"])) {
                $results["comments"] = "";
            }
        }

        echo Zend_Json::encode($results);
        $this->removeViewRenderer();
    }

}
