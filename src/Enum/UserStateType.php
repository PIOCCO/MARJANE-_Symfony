<?php
namespace App\Doctrine\Enum;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Enum\UserState;

class UserStateType extends Type {
    const USERSTATE = 'userstate'; // Custom type name

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return UserState::from($value); // Convert DB value to enum
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof UserState ? $value->value : $value; // Convert enum to DB value
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('New', 'Active', 'Blocked', 'Banned')"; // SQL declaration for enum
    }

    public function getName()
    {
        return self::USERSTATE;
    }
}
