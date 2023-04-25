<?php

namespace App\Entity;

use App\Repository\LigneFraisForfaitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneFraisForfaitRepository::class)]
class LigneFraisForfait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;
    /**
    *@param FicheFrais|null $fichefrais
     *@param FicheFrais|null $fichefrais
     * @param int|null $quantite
    */
    public function __construct(?FicheFrais $ficheFrais, ?FraisForfait $fraisForfait, ?int $quantite){
        $this->ficheFrais = $ficheFrais;
        $this->fraisForfait = $fraisForfait;
        $this->quantite = $quantite;
    }
    #[ORM\ManyToOne(inversedBy: 'lignefraisforfait')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FicheFrais $ficheFrais = null;

    #[ORM\ManyToOne(inversedBy: 'ligneFraisForfait', fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FraisForfait $fraisForfait = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

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

    public function getFraisForfait(): ?FraisForfait
    {
        return $this->fraisForfait;
    }

    public function setFraisForfait(?FraisForfait $fraisForfait): self
    {
        $this->fraisForfait = $fraisForfait;

        return $this;
    }

}
