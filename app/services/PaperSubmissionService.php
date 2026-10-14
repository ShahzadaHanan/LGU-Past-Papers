<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PaperSubmissionRepository;

class PaperSubmissionService
{
    private const VALID_EXAM_TYPES = ['mids', 'finals', 'summer_mids', 'summer_finals'];

    public function __construct(
        private PaperSubmissionRepository $repository,
        private PaperService $paperService
    ) {}

    public function getAll(): array { return $this->repository->all(); }
    public function getPending(): array { return $this->repository->getByStatus('pending'); }
    public function getById(int $id) { return $this->repository->find($id); }
    public function create(array $data): bool { return $this->repository->create($data); }
    public function update(int $id, array $data): bool { return $this->repository->update($id, $data); }
    public function delete(int $id): bool { return $this->repository->delete($id); }

    /**
     * Approving a submission classifies and publishes it in one step: the
     * admin supplies what the public form couldn't collect (degree program,
     * exam type, session), the submitted image is adopted into the real
     * Papers catalogue, and the submission is linked to the paper it became
     * so it can't be published twice.
     *
     * @return int The new paper's id.
     */
    public function approveAndPublish(int $submissionId, array $paperData, ?array $solutionImage = null): int
    {
        $submission = $this->repository->find($submissionId);

        if (!$submission) {
            throw new \Exception('Submission not found.');
        }

        if ($submission->published_paper_id) {
            throw new \Exception('This submission has already been published as a paper.');
        }

        if (
            empty($paperData['sub_department_id'])
            || empty($paperData['exam_type'])
            || empty($paperData['session'])
            || empty($paperData['subject_name'])
        ) {
            throw new \Exception('Please select a degree program, exam type, session and subject name.');
        }

        if (!in_array($paperData['exam_type'], self::VALID_EXAM_TYPES, true)) {
            throw new \Exception('Invalid exam type selected.');
        }

        $paperId = $this->paperService->publishFromSubmission($paperData, $submission->image_path, $solutionImage);

        if (!$paperId) {
            throw new \Exception('Failed to create the paper record.');
        }

        $this->repository->markPublished($submissionId, $paperId);

        return $paperId;
    }

    public function reject(int $id): bool
    {
        return $this->repository->updateStatus($id, 'rejected');
    }
}
