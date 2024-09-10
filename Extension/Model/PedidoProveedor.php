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
            $database = new DataBase();
            $data = $database->select($this->getSqlServed($this->idpedido));
            $this->servido = $data['servido'] ?? $this->servido;
            return true;
        };
    }

    public function getSqlPartial(): Closure
    {
        return function (int $id): string {
            return 'SELECT ' . PedidoPendiente::SERVED_PARTIAL
                . ' FROM lineaspedidosprov t3'
                . ' WHERE t3.idpedido = ' . $id
                . ' AND t3.servido >= t3.cantidad'
                . ' LIMIT 1';
        };
    }

    public static function getSqlServed(): Closure
    {
        return function (int $id): string {
            return 'SELECT CASE WHEN t2.editable = false'
                . ' THEN ' . PedidoPendiente::SERVED_COMPLETE
                . ' ELSE COALESCE((' . $this->getSqlPartial($id) . '), ' . PedidoPendiente::SERVED_NONE . ')'
                . ' END servido'
                . ' FROM pedidosprov t1'
                . ' INNER JOIN estados_documentos t2 ON t2.idestado = t1.idestado'
                . ' WHERE t1.idpedido = ' . $id;
        };
    }
}
