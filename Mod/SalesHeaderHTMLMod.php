<?php
/**
 * This file is part of PedidosPendientes plugin for FacturaScripts.
 * FacturaScripts    Copyright (C) 2015-2024 Carlos Garcia Gomez <carlos@facturascripts.com>
 * PedidosPendientes Copyright (C) 2023-2024 Jose Antonio Cuello Principal <yopli2000@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Plugins\PedidosPendientes\Mod;

use FacturaScripts\Core\Base\Contract\SalesModInterface;
use FacturaScripts\Core\Base\Translator;
use FacturaScripts\Core\Model\Base\SalesDocument;
use FacturaScripts\Core\Model\User;
use FacturaScripts\Plugins\PedidosPendientes\Lib\PedidosPendientes\PedidoPendiente;

/**
 * Add new fields in the modal window of the document header
 *   - servido: Determines whether the document is pending service.
 *
 * @author Jose Antonio Cuello Principal <yopli2000@gmail.com>
 */
class SalesHeaderHTMLMod implements SalesModInterface
{
    public function apply(SalesDocument &$model, array $formData, User $user)
    {
    }

    public function applyBefore(SalesDocument &$model, array $formData, User $user)
    {
    }

    public function assets(): void
    {
    }

    public function newBtnFields(): array
    {
        return [];
    }

    public function newFields(): array
    {
        return [];
    }

    public function newModalFields(): array
    {
        return ['servido'];
    }

    public function renderField(Translator $i18n, SalesDocument $model, string $field): ?string
    {
        if ($field == 'servido') {
            return $this->servido($i18n, $model);
        }

        return null;
    }

    private static function servido(Translator $i18n, SalesDocument $model): string
    {
        if (false === property_exists($model, 'servido')) {
            return '';
        }

        $values = [
            PedidoPendiente::SERVED_NONE => $i18n->trans('pending'),
            PedidoPendiente::SERVED_PARTIAL => $i18n->trans('partial'),
            PedidoPendiente::SERVED_COMPLETE => $i18n->trans('completed'),
        ];

        return '<div class="col-sm-6">'
            . '<div class="form-group">'
            . $i18n->trans('served')
            . '<input type="text" class="form-control" disabled value="' . $values[$model->servido] . '" />'
            . '</div>'
            . '</div>';
    }
}
