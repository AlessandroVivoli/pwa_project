<?php
class User
{
    public string $uuid;
    public string $username;
    public int $level;

    public function __construct($uuid, $username, $level)
    {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->level = $level;
    }
}
