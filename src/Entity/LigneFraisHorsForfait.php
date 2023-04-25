<?php

namespace App\Entity;

use App\Repository\LigneFraisHorsForfaitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneFraisHorsForfaitRepository::class)]
class LigneFraisHorsForfait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montant = null;

    public function __construct(?FicheFrais $ficheFrais, ?string $libelle, ?\DateTime $date, ?string $montant){
        $this->ficheFrais = $ficheFrais;
        $this->libelle = $libelle;
        $this->date = $date;
        $this->montant = $montant;
    }

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'ligneFraisHorsForfait')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FicheFrais $ficheFrais = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getFicheFrais(): ?FicheFrais
    {
        return $this->ficheFrais;
    }

    public function setFicheFrais(?FicheFrais $ficheFrais): self
    {
        $this->ficheFrais = $ficheFrais;

        return $this;
    }
}
