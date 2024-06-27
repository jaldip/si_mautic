<?php

namespace Mautic\LeadBundle\Model;

class SegmentActionModel
{
    public function __construct(
        private LeadModel $contactModel
    ) {
    }

    public function addContacts(array $contactIds, array $segmentIds): void
    {
        $contacts = $this->contactModel->getLeadsByIds($contactIds);

        foreach ($contacts as $contact) {
            if (!$this->contactModel->canEditContact($contact)) {
                continue;
            }

            $this->contactModel->addToLists($contact, $segmentIds);
        }

        $this->contactModel->saveEntities($contacts);
        // Create and write to a custom log file
        $path    = '/var/www/html/mautic/var/logs/trigger_contact.txt';
        $data    = $this->contactModel->saveEntities($contacts);
        file_put_contents($path, '-> Mautic_test_segment_contact -> '.$data.' -> '.date('Y-m-d H:i:s').PHP_EOL, FILE_APPEND);
    }

    public function removeContacts(array $contactIds, array $segmentIds): void
    {
        $contacts = $this->contactModel->getLeadsByIds($contactIds);

        foreach ($contacts as $contact) {
            if (!$this->contactModel->canEditContact($contact)) {
                continue;
            }

            $this->contactModel->removeFromLists($contact, $segmentIds);
        }

        $this->contactModel->saveEntities($contacts);
    }
}
