<?php


namespace AllCoinCore\Model;


use DateTime;

class AssetPair implements ModelInterface
{
    private ?Order $lastOrder;

    public function __construct(
        private string $id,
        private string $name,
        private DateTime $createdAt,
        private ?DateTime $updatedAt = null,
    )
    {
        $this->lastOrder = null;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Order|null
     */
    public function getLastOrder(): ?Order
    {
        return $this->lastOrder;
    }

    /**
     * @param Order|null $lastOrder
     */
    public function setLastOrder(?Order $lastOrder): void
    {
        $this->lastOrder = $lastOrder;
    }


}
