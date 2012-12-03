<?php

class Commenting_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface
{
    public static function install()
    {
        Pimcore_API_Plugin_Abstract::getDb()->getResource()->query(
            "CREATE TABLE IF NOT EXISTS `plugin_commenting_comments` (
                `Id` INT NOT NULL AUTO_INCREMENT,
                `type` ENUM( 'object', 'asset', 'document' ) NOT NULL ,
                `commentingTargetId` INT NOT NULL ,
                `userId` INT NULL ,
                `data` TEXT NULL ,
                `metadata` TEXT NULL ,
                `date` INT NULL ,
                PRIMARY KEY  (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        if (self::isInstalled()) {
            $statusMessage = "Commenting Plugin successfully installed.";
        } else {
            $statusMessage = "Commenting Plugin could not be installed";
        }
        return $statusMessage;
    }

    public static function uninstall()
    {
        Pimcore_API_Plugin_Abstract::getDb()->getResource()->query(
            "DROP TABLE `plugin_commenting_comments`");

        if (!self::isInstalled()) {
            $statusMessage = "Commenting Plugin successfully uninstalled.";
        } else {
            $statusMessage = "Commenting Plugin could not be uninstalled";
        }
        return $statusMessage;
    }

    public static function isInstalled()
    {
        try {
            Pimcore_API_Plugin_Abstract::getDb()->describeTable("plugin_commenting_comments");
            return true;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public static function getTranslationFileDirectory()
    {
        return PIMCORE_PLUGINS_PATH . "/Commenting/texts";
    }

    /**
     * @param string $language
     * @return string path to the translation file relative to plugin direcory
     */
    public static function getTranslationFile($language)
    {
        if (is_file(PIMCORE_PLUGINS_PATH . "/Commenting/texts/" . $language . ".csv")) {
            return "/Commenting/texts/" . $language . ".csv";
        } else {
            return "/Commenting/texts/en.csv";
        }
    }

    public function preDeleteDocument(Document $document)
    {
        $this->deleteAllForTarget($document);
    }

    public function preDeleteObject(Object_Abstract $object)
    {
        $this->deleteAllForTarget($object);
    }

    public function preDeleteAsset(Asset $asset)
    {
        $this->deleteAllForTarget($asset);
    }

    /**
     * Deletes all comments for a given targets
     * @param Element_Interface $target
     */
    private function deleteAllForTarget($target)
    {
        $resourceComment = new Resource_Comment();
        $resourceComment->setCommentingTarget($target);
        $resourceComment->deleteAllForTarget();
    }

    /**
     * deletes comment by id
     * @param string|integer $id comment id
     */
    public static function deleteComment($id)
    {
        $comment = Resource_Comment::getById($id);
        $comment->delete();
    }

    /**
     * @param integer $comment
     * @param integer $date
     * @param Element_Interface $target
     * @param Object_Abstract $user
     * @param array $metadata
     */
    public static function postComment($comment, $date, $target, $user = null, array $metadata = array())
    {
        $type = self::getTypeFromTarget($target);

        if (!empty($type)) {
            $resourceComment = new Resource_Comment();
            $resourceComment->setCommentingTarget($target);
            if ($user instanceof Object_Concrete) {
                $resourceComment->setUser($user);
            }
            $resourceComment->setData($comment);
            $resourceComment->setDate($date);
            $resourceComment->setType($type);
            $resourceComment->setMetadata($metadata);
            $resourceComment->save();
        } else {
            logger::log("Commenting_Plugin: Could not post comment, unknown resource", Zend_Log::ERR);
        }
    }

    /**
     * @param Element_Interface $target
     * @param string[]|string $orderkey an array of the following string(s) userId, date, metadata, data, Id
     * @param string $order asc | desc
     * @return Resource_Comment[] $comments
     */
    public static function getComments($target, $orderKey = null, $order = null)
    {
        if ($target instanceof Element_Interface) {
            $list = new Resource_Comment_List($target);
            if (!empty($orderKey)) {
                $list->setOrderKey($orderKey);
            }
            if (strtolower($order) == "asc" || strtolower($order) == "desc") {
                $list->setOrder($order);
            }
            return $list->load();
        }
    }

    /**
     * @param Element_Interface $target
     */
    public static function getTypeFromTarget(Element_Interface $target)
    {
        $type = "";
        if ($target instanceof Document) {
            $type = "document";
        } else if ($target instanceof Asset) {
            $type = "asset";
        } else if ($target instanceof Object_Abstract) {
            $type = "object";
        }
        return $type;
    }

}
