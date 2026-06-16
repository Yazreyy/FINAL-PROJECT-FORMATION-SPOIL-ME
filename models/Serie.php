<?php

class Serie{

public function __construct(private string $title,
private string $synopsis, private string $release_date, private string $image,
private float $average_rating, private string $status, private string $created_at,
private ?int $created_by = null,
private ?int $id = null, private ?int $tmdb_id = null,
private ?string $format = null, private ?string $creator = null,
private ?string $country = null, private ?string $language = null,
private ?int $nb_saisons = null, private ?int $nb_episodes = null, private ?string $backdrop = null)
{
}

public function getId() : ?int { return $this->id; }
public function setId(?int $id) : void { $this->id = $id; }

public function getTmdbId() : ?int { return $this->tmdb_id; }
public function setTmdbId(?int $tmdb_id) : void { $this->tmdb_id = $tmdb_id; }

public function getTitle() : string { return $this->title; }
public function setTitle(string $title) : void { $this->title = $title; }

public function getSynopsis() : string { return $this->synopsis; }
public function setSynopsis(string $synopsis) : void { $this->synopsis = $synopsis; }

public function getReleaseDate() : string { return $this->release_date; }
public function setReleaseDate(string $release_date) : void { $this->release_date = $release_date; }

public function getImage() : string { return $this->image; }
public function setImage(string $image) : void { $this->image = $image; }

public function getAverageRating() : float { return $this->average_rating; }
public function setAverageRating(float $average_rating) : void { $this->average_rating = $average_rating; }

public function getCreatedBy() : ?int { return $this->created_by; }
public function setCreatedBy(?int $created_by) : void { $this->created_by = $created_by; }

public function getStatus() : string { return $this->status; }
public function setStatus(string $status) : void { $this->status = $status; }

public function getCreatedAt() : string { return $this->created_at; }
public function setCreatedAt(string $created_at) : void { $this->created_at = $created_at; }

public function isFinish() : bool { return $this->status === 'terminée'; }

public function getFormat() : ?string { return $this->format; }
public function setFormat(?string $format) : void { $this->format = $format; }

public function getCreator() : ?string { return $this->creator; }
public function setCreator(?string $creator) : void { $this->creator = $creator; }

public function getCountry() : ?string { return $this->country; }
public function setCountry(?string $country) : void { $this->country = $country; }

public function getLanguage() : ?string { return $this->language; }
public function setLanguage(?string $language) : void { $this->language = $language; }

public function getSaisons() : ?int { return $this->nb_saisons;}
public function setSaisons(?int $nb_saisons) : void { $this->nb_saisons = $nb_saisons;}

public function getEpisodes() : ?int { return $this->nb_episodes;}
public function setEpisodes(?int $nb_episodes) : void { $this->nb_episodes = $nb_episodes;}

public function getBackdrop() : ?string { return $this->backdrop;}
public function setBackdrop(?string $backdrop) : void { $this->backdrop = $backdrop;}
}
