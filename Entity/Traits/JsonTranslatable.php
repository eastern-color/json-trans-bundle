<?php

namespace EasternColor\JsonTransBundle\Entity\Traits;

use EasternColor\JsonTransBundle\Annotations\JsonTrans;
use EasternColor\JsonTransBundle\JsonTranslate;
use JMS\Serializer\Annotation\Groups;

trait JsonTranslatable
{
    /**
     * @var string
     *
     * @Groups({"common"})
     *
     * @JsonTrans
     * @ORM\Column(name="json_trans", type="json_array", nullable=true)
     */
    private $jsonTrans;

    /**
     * Get the value of Json Trans.
     *
     * @return string
     */
    public function getJsonTrans()
    {
        return $this->jsonTrans;
    }

    /**
     * Set the value of Json Trans.
     *
     * @param string jsonTrans
     *
     * @return self
     */
    public function setJsonTrans($jsonTrans)
    {
        $this->jsonTrans = $jsonTrans;

        return $this;
    }

    public function getTranslate()
    {
        return new JsonTranslate($this->jsonTrans, $this);
    }
}
