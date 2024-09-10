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
namespace FacturaScripts\Plugins\PedidosPendientes\Extension\Controller;

use Closure;
use FacturaScripts\Core\Model\Base\TransformerDocument;

/**
 * Description of DocumentStitcher
 *
 * @author Jose Antonio Cuello Principal <yopli2000@gmail.com>
 *
 * @property TransformerDocument[] $documents
 */
class DocumentStitcher
{

    protected function checkPrototype(): Closure
    {
        return function ($prototype, $newLines) {
            foreach ($this->documents as $doc) {
                if (in_array($doc->modelClassName(), ['PedidoCliente', 'PedidoProveedor'])) {
                    $doc->save();
                }
            }
            return true;
        };
    }
}
