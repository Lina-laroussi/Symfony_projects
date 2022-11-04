<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $coef = null;

    #[ORM\Column(length: 255)]
    private ?string $module = null;

    #[ORM\Column]
    private ?int $valeur = null;

    #[ORM\ManyToOne(inversedBy: 'note')]
    #[JoinColumn(name: "etudiant", referencedColumnName: 'cin')]
    private ?Etudiant $etudiant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoef(): ?int
    {
        return $this->coef;
    }

    public function setCoef(int $coef): self
    {
        $this->coef = $coef;

        return $this;
    }

    public function getModule(): ?string
    {
        return $this->module;
    }

    public function setModule(string $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }
}
