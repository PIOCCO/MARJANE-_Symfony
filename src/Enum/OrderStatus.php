<?php


// src/Enum/OrderStatus.php

namespace App\Enum;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELED = 'canceled';
}
