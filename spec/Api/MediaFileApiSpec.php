<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\ListableResourceInterface;
use Akeneo\Pim\Api\MediaFileApi;
use Akeneo\Pim\Api\MediaFileApiInterface;
use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;

class MediaFileApiSpec extends ObjectBehavior
{
    function let(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->beConstructedWith($resourceClient, $pageFactory, $cursorFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MediaFileApi::class);
        $this->shouldImplement(MediaFileApiInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
    }

    function it_returns_a_media_file($resourceClient)
    {
        $mediaFileCode = '3/e/42.jpg';
        $mediaFile = [
            'code' => '3/e/42.jpg',
            'original_filename' => '42.jpg',
            'mime_type' => 'image/jpeg',
        ];

        $resourceClient
            ->getResource(MediaFileApi::MEDIA_FILE_PATH, [$mediaFileCode])
            ->willReturn($mediaFile);

        $this->get($mediaFileCode)->shouldReturn($mediaFile);
    }

    function it_returns_a_list_of_media_files_with_default_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(MediaFileApi::MEDIA_FILES_PATH, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_media_files_with_limit_and_count($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(MediaFileApi::MEDIA_FILES_PATH, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_media_files(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(MediaFileApi::MEDIA_FILES_PATH, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_media_files_with_additional_query_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(MediaFileApi::MEDIA_FILES_PATH, [], null, null, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(null, null, ['foo' => 'bar'])->shouldReturn($page);
    }
}