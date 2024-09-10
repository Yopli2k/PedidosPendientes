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
namespace FacturaScripts\Plugins\PedidosPendientes\Extension\Model;

use Closure;
use FacturaScripts\Core\Base\DataBase;
use FacturaScripts\Plugins\PedidosPendientes\Lib\PedidosPendientes\PedidoPendiente;

/**
 * Description of PedidoProveedor
 *
 * @author Jose Antonio Cuello Principal <yopli2000@gmail.com>
 *
 * @property bool $editable
 * @property int $idpedido
 * @property int $servido
 */
class PedidoProveedor
{
    public function clear(): Closure
    {
        return function () {
            $this->servido = PedidoPendiente::SERVED_NONE;
        };
    }

    public function saveUpdateBefore(): Closure
    {
        return function (): bool {
            if (false === $this->editable) {
                $this->servido = PedidoPendiente::SERVED_COMPLETE;
                return true;
            }

            $database = new DataBase();
            $data = $database->select($this->getSqlServed($this->idpedido));
            $this->servido = $data[0]['servido'] ?? $this->servido;
            return true;
        };
    }

    protected function getSqlPartial(): Closure
    {
        return function (int $id): string {
            return 'SELECT ' . PedidoPendiente::SERVED_PARTIAL
                . ' FROM lineaspedidosprov'
                . ' WHERE idpedido = ' . $id
                . ' AND servido >= cantidad'
                . ' LIMIT 1';
        };
    }

    protected function getSqlServed(): Closure
    {
        return function (int $id): string {
            $sqlPartial = '(' . $this->getSqlPartial($id) . ')';
            return 'SELECT COALESCE(' . $sqlPartial . ', ' . PedidoPendiente::SERVED_NONE . ') AS servido'
                . ' FROM pedidosprov'
                . ' WHERE idpedido = ' . $id;
        };
    }
}
