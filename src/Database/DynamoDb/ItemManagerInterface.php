<?php


namespace AllCoinCore\Database\DynamoDb;


use AllCoinCore\Database\DynamoDb\Exception\ItemDeleteException;
use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;

interface ItemManagerInterface
{
    /**
     * @param array $data
     * @param string $partitionKey
     * @param string $sortKey
     * @throws ItemSaveException
     */
    public function save(array $data, string $partitionKey, string $sortKey): void;

    /**
     * @param string $partitionKey
     * @return array
     * @throws ItemReadException
     */
    public function fetchAll(string $partitionKey): array;

    /**
     * @param string $partitionKey
     * @param string $sortKey
     * @return array
     * @throws ItemReadException
     */
    public function fetchOne(string $partitionKey, string $sortKey): array;

    /**
     * @param string $partitionKey
     * @param string $sortKey
     * @throws ItemDeleteException
     */
    public function delete(string $partitionKey, string $sortKey): void;

    /**
     * @param string $partitionKey
     * @param string $lsiKeyName
     * @param string $lsiKey
     * @return array
     * @throws ItemReadException
     */
    public function fetchAllOnLSI(string $partitionKey, string $lsiKeyName, string $lsiKey): array;

    /**
     * @param string $partitionKey
     * @param string $start
     * @param string $end
     * @return array
     * @throws ItemReadException
     */
    public function fetchAllBetween(string $partitionKey, string $start, string $end): array;

    /**
     * @param string $partitionKey
     * @param string $lsiKeyName
     * @param string $lsiKey
     * @return array
     * @throws ItemReadException
     */
    public function fetchOneOnLSI(string $partitionKey, string $lsiKeyName, string $lsiKey): array;

    /**
     * @param string $partitionKey
     * @param string $sortKey
     * @return array
     * @throws ItemReadException
     */
    public function fetchLast(string $partitionKey, string $sortKey): array;
}
