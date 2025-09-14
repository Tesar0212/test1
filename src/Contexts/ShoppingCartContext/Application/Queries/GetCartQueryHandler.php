<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Queries;

use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Exceptions\UseCaseException;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Queries\Result\CartItemView;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Queries\Result\CartView;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Queries\Result\CustomerView;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Application\Queries\Result\ProductView;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Repositories\CartRepositoryInterface;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Repositories\CustomerRepositoryInterface;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Domain\Repositories\ProductRepositoryInterface;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Logger\LoggerInterface;

readonly class GetCartQueryHandler
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private ProductRepositoryInterface  $productRepository,
        private CartRepositoryInterface     $cartRepository,
        private LoggerInterface             $logger
    )
    {
    }

    /**
     * @throws UseCaseException
     */
    public function __invoke(GetCartQuery $query): CartView
    {
        try {

            $cartRawData = $this->cartRepository->findRaw($query->cartUuid);

            $cartItems = $cartRawData['item'] ?? [];

            $productIds = array_unique(
                array_values(
                    array_map(
                        fn(array $cartItem) => $cartItem['productUuid'] ?? null,
                        $cartItems
                    )
                )
            );

            $products = $this->productRepository->findRawByIds($productIds);

            $customer = $this->customerRepository->findRawByCartUuid($query->cartUuid);

            $productsIndexedByUuid = array_reduce(
                $products,
                static function (array $carry, array $product): array {
                    if (!empty($product['uuid'])) {
                        $carry[$product['uuid']] = $product;
                    }
                    return $carry;
                },
                []
            );

            $totalCost = $this->calculateTotalCost($productsIndexedByUuid, $cartItems);

            return new CartView(
                uuid: $cartRawData['uuid'],
                paymentMethod: $cartRawData['paymentMethod'],
                items: $this->makeCartItems($productsIndexedByUuid, $cartItems),
                customer: $this->makeCustomer($customer),
                total: $totalCost
            );
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['cartUuid' => $query->cartUuid]);
            throw new UseCaseException();
        }
    }

    /**
     * @return CartItemView[]
     */
    private function makeCartItems(array $productsIndexedByUuid, array $cartItems): array
    {
        return array_map(static function (array $cartItem) use ($productsIndexedByUuid) {
            $product = $productsIndexedByUuid[$cartItem['productUuid']];

            return new CartItemView(
                uuid: $cartItem['uuid'],
                quantity: $cartItem['quantity'],
                total: ($product['price'] ?? 0) * $cartItem['quantity'],
                product: new ProductView(
                    uuid: $product['uuid'],
                    name: $product['name'],
                    thumbnail: $product['thumbnail'],
                    price: $product['price'],
                )
            );
        }, $cartItems);
    }

    private function calculateTotalCost(array $productsIndexedByUuid, array $items): float
    {
        return array_sum(
            array_map(
                static fn(array $cartItem) => ($productsIndexedByUuid[$cartItem['productUuid']]['price'] ?? 0) * $cartItem['quantity'],
                $items
            )
        );
    }

    private function makeCustomer(array $customer): CustomerView
    {
        return new CustomerView(
            id: $customer['id'],
            name: implode(' ', [
                    $customer['firstName'],
                    $customer['lastName'],
                    $customer['middleName']]
            ),
            email: $customer['email'],
        );
    }
}