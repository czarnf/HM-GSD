<?php

namespace app\services\impl;

interface TransactionServiceImpl
{
    function setTransaction(array $data): string;
    function getTransactions(): string;
    function getTransactionByUserId(int $user_id): string;
    function updateTransactionStatus(int $status, string $slug): string;

    function setWithdrawal(array $request): string;
    function getWithdrawalByUserId(int $request): string;
    function getWithdrawals(): string;
    function updateWithdrawalStatus(string $slug, int $status): string;
}
