<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\VideoRepository;
use App\Models\Video;

class VideoService
{
    public function __construct(private VideoRepository $repository) {}

    public function getAllVideos(): array
    {
        return $this->repository->all();
    }

    public function getVideoById(int $id): ?Video
    {
        return $this->repository->find($id);
    }

    public function createVideo(array $data): bool
    {
        return $this->repository->create($this->prepareVideoData($data));
    }

    public function updateVideo(int $id, array $data): bool
    {
        return $this->repository->update($id, $this->prepareVideoData($data));
    }

    public function deleteVideo(int $id): bool
    {
        return $this->repository->delete($id);
    }

    private function prepareVideoData(array $data): array
    {
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        if (!empty($data['youtube_url'])) {
            $data['youtube_video_id'] = $this->extractYoutubeVideoId($data['youtube_url']);
        }

        return $data;
    }

    private function extractYoutubeVideoId(string $url): ?string
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        if (preg_match($pattern, $url, $match)) {
            return $match[1];
        }
        return null;
    }
}
