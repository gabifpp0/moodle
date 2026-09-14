<?php

namespace local_avisos\local;

defined('MOODLE_INTERNAL') || die();

class manager //aqui vai ficar a lógica de negócio do plugin, como salvar, deletar, buscar, etc
{
    public function get_all(): array
    {
        global $DB;

        return $DB->get_records(
            'local_avisos',
            null,
            'timecreated DESC'
        );
    }

    public function get(int $id): \stdClass
    {
        global $DB;

        return $DB->get_record(
            'local_avisos',
            ['id' => $id],
            '*',
            MUST_EXIST
        );
    }

    public function get_visible(): array
    {
        global $DB;

        $now = time();

        $select = '
            enabled = :enabled

            AND (
                timestart = 0
                OR timestart <= :nowstart
            )

            AND (
                timeend = 0
                OR timeend >= :nowend
            )
        ';

        $params = [
            'enabled' => 1,
            'nowstart' => $now,
            'nowend' => $now,
        ];

        return $DB->get_records_select(
            'local_avisos',
            $select,
            $params,
            'timecreated DESC'
        );
    }

    public function save(\stdClass $data): int
    {
        global $DB;

        $now = time();

        if (!empty($data->id)) {

            $record = new \stdClass();

            $record->id = $data->id;
            $record->title = trim($data->title);
            $record->message = trim($data->message);
            $record->enabled = !empty($data->enabled) ? 1 : 0;
            $record->timestart = $data->timestart ?? 0;
            $record->timeend = $data->timeend ?? 0;
            $record->timemodified = $now;

            $DB->update_record(
                'local_avisos',
                $record
            );

            return $record->id;
        }

        $record = new \stdClass();

        $record->title = trim($data->title);
        $record->message = trim($data->message);
        $record->enabled = !empty($data->enabled) ? 1 : 0;
        $record->timestart = $data->timestart ?? 0;
        $record->timeend = $data->timeend ?? 0;
        $record->timecreated = $now;
        $record->timemodified = $now;

        return $DB->insert_record( //
            'local_avisos',
            $record
        );
    }

    public function delete(int $id): void
    {
        global $DB;

        $DB->delete_records(
            'local_avisos',
            ['id' => $id]
        );
    }

    public function toggle(int $id): void
    {
        global $DB;

        $record = $this->get($id);

        $record->enabled = $record->enabled ? 0 : 1;
        $record->timemodified = time();

        $DB->update_record(
            'local_avisos',
            $record
        );
    }

}