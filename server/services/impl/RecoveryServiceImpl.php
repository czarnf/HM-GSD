<?php

namespace app\services\impl;

interface RecoveryServiceImpl
{
    function setRecovery(array $data): string;
    function getRecoveries(): string;
    function updateRecoveryStatus(int $status, string $depositSlug): string;


    // setWR
    function setWR(array $data): string;

    // getWRRejected
    function getWRRejected(): string;

    // getWRAccepted
    function getWRAccepted(): string;

    // getWRNew
    function getWRNew(): string;

    // editWR
    function rejectWR(string $message, string $slug): string;
    // acceptRequest
    function acceptWR(int $user_id, string $slug, string $amount): string;
}
