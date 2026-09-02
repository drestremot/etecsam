<?php

namespace App\Traits;

use App\Services\AuditLogger;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function (Model $model) {
            $module = $model->getAuditModuleName();
            $label = $model->getAuditRecordLabel();

            $attributes = $model->getAttributes();
            $filteredAttributes = $model->filterAuditAttributes($attributes);

            AuditLogger::log(
                action: 'created',
                module: $module,
                description: "Cadastrou novo registro em {$module}: {$label}",
                auditable: $model,
                oldValues: null,
                newValues: $filteredAttributes
            );
        });

        static::updated(function (Model $model) {
            $module = $model->getAuditModuleName();
            $label = $model->getAuditRecordLabel();

            $changes = $model->getChanges();
            // Remove updated_at if it's the only changed column
            unset($changes['updated_at']);

            if (empty($changes)) {
                return;
            }

            $original = $model->getOriginal();
            $oldValues = [];
            $newValues = [];

            foreach ($changes as $key => $newValue) {
                if (in_array($key, $model->getHiddenAuditFields())) {
                    continue;
                }
                $oldValues[$key] = $original[$key] ?? null;
                $newValues[$key] = $newValue;
            }

            if (empty($newValues)) {
                return;
            }

            AuditLogger::log(
                action: 'updated',
                module: $module,
                description: "Atualizou registro em {$module}: {$label}",
                auditable: $model,
                oldValues: $oldValues,
                newValues: $newValues
            );
        });

        static::deleted(function (Model $model) {
            $module = $model->getAuditModuleName();
            $label = $model->getAuditRecordLabel();

            $attributes = $model->getAttributes();
            $filteredAttributes = $model->filterAuditAttributes($attributes);

            AuditLogger::log(
                action: 'deleted',
                module: $module,
                description: "Excluiu registro de {$module}: {$label}",
                auditable: $model,
                oldValues: $filteredAttributes,
                newValues: null
            );
        });
    }

    public function getAuditModuleName(): string
    {
        $class = class_basename(static::class);

        return match ($class) {
            'Task'                  => 'Demandas (KanbanTec)',
            'LabReservation'        => 'Reservas de Lab',
            'MedicalCertificate'    => 'Atestados Médicos',
            'LegalLeave',
            'LegalLeaveRequest'     => 'Folgas Legais',
            'VanReservation'        => 'Van Escolar',
            'Course', 'Subject'     => 'Cursos & Grade',
            'User'                  => 'Usuários & Perfis',
            'Teacher'               => 'Docentes no Site',
            'Space'                 => 'Espaços Didáticos',
            'Material'              => 'Inventário',
            'Laboratory'            => 'Laboratórios',
            'Department'            => 'Departamentos',
            'Unit'                  => 'Unidades',
            'Sector'                => 'Setores',
            default                 => $class,
        };
    }

    public function getAuditRecordLabel(): string
    {
        return $this->name
            ?? $this->title
            ?? $this->subject
            ?? ($this->getKey() ? "#{$this->getKey()}" : 'Novo Item');
    }

    public function getHiddenAuditFields(): array
    {
        return ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes', 'created_at', 'updated_at'];
    }

    public function filterAuditAttributes(array $attributes): array
    {
        $hidden = $this->getHiddenAuditFields();
        foreach ($hidden as $field) {
            unset($attributes[$field]);
        }
        return $attributes;
    }
}

