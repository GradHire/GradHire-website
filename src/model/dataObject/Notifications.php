<?php

namespace app\src\model\dataObject;

class Notifications extends AbstractDataObject
{
    private int $idnotification;
    private string $notification;
    private int $idutilisateur;
    private string $date;
    private string $url;
    private bool $lu;

    public function __construct(array $dataObjectFormatTableau)
    {
        foreach ($dataObjectFormatTableau as $key => $value) {
            $this->$key = $value;
        }
    }

    public function isLu(): bool
    {
        return $this->lu;
    }

    public function setLu(bool $lu): void
    {
        $this->lu = $lu;
    }

    public function getIdnotification(): int
    {
        return $this->idnotification;
    }

    public function setIdnotification(int $idnotification): void
    {
        $this->idnotification = $idnotification;
    }

    public function getNotification(): string
    {
        return $this->notification;
    }

    public function setNotification(string $notification): void
    {
        $this->notification = $notification;
    }

    public function getIdutilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(int $idutilisateur): void
    {
        $this->idutilisateur = $idutilisateur;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }
}