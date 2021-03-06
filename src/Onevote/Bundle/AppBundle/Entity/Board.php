<?php

namespace Onevote\Bundle\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Board
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Onevote\Bundle\AppBundle\Entity\Repository\BoardRepository")
 */
class Board
{
    use Timestampable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, unique=true)
     */
    private $hash;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var Choice[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Choice", mappedBy="board", cascade={"all"}, orphanRemoval=true)
     */
    private $choices;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->hash = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->choices = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Board
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Board
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Board
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Add choices
     *
     * @param Choice $choice
     * @return Board
     */
    public function addChoice(Choice $choice)
    {
        $choice->setBoard($this);

        $this->choices[] = $choice;

        return $this;
    }

    /**
     * Remove choice
     *
     * @param Choice $choice
     */
    public function removeChoice(Choice $choice)
    {
        $this->choices->removeElement($choice);
    }

    /**
     * Get choices
     *
     * @return Collection
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * Get all of votes of choices
     *
     * @return array
     */
    public function getVotes()
    {
        $votes = [];
        foreach ($this->choices as $choice) {
            $votes = array_merge($votes, $choice->getVotes()->toArray());
        }

        return $votes;
    }
}
