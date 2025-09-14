<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Contexts\ProductCatalogContext\Application\Queries;

use Raketa\BackendTestTask\Contexts\ProductCatalogContext\Application\Exceptions\UseCaseException;
use Raketa\BackendTestTask\Contexts\ProductCatalogContext\Application\Queries\Result\ProductsView;
use Raketa\BackendTestTask\Contexts\ProductCatalogContext\Domain\Repositories\ProductRepositoryInterface;
use Raketa\BackendTestTask\Contexts\ShoppingCartContext\Infrastructure\Logger\LoggerInterface;
use Throwable;

readonly class GetProductByCategoryQueryHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private LoggerInterface            $logger
    )
    {
    }

    /**
     * @param GetProductByCategoryQuery $query
     * @return ProductsView[]
     * @throws UseCaseException
     */
    public function __invoke(GetProductByCategoryQuery $query): array
    {
        try {
            $rawProducts = $this->productRepository->getByCategoryRaw($query->category);
            return array_map(
                static fn(array $row): ProductsView => $this->make($row),
                $rawProducts
            );
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['category' => $query->category]);
            throw new UseCaseException('Не удалось получить продукты', previous: $exception);
        }
    }

    private function make(array $row): ProductsView
    {
        return new ProductsView(
            id: $row['id'],
            uuid: $row['uuid'],
            isActive: $row['is_active'],
            category: $row['category'],
            name: $row['name'],
            description: $row['description'],
            thumbnail: $row['thumbnail'],
            price: $row['price'],
        );
    }
}