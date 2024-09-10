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
use FacturaScripts\Core\Tools;
use FacturaScripts\Dinamic\Lib\PedidosPendientes\PedidoPendiente;

/**
 * Extension of ListPedido Controller
 *
 * @author José Antonio Cuello Principal <yopli2000@gmail.com>
 */
class ListPedidoProveedor
{
    /**
     * Create the view to display.
     *   - Add servido filter
     */
    public function createViews(): Closure
    {
        return function () {
            if (isset($this->views['ListPedidoProveedor'])) {
                $options = [
                    ['code' => '', 'description' => '------'],
                    ['code' => PedidoPendiente::SERVED_NONE, 'description' => Tools::lang()->trans('pending')],
                    ['code' => PedidoPendiente::SERVED_PARTIAL, 'description' => Tools::lang()->trans('partial')],
                    ['code' => PedidoPendiente::SERVED_COMPLETE, 'description' => Tools::lang()->trans('completed')],
                ];
                $this->addFilterSelect('ListPedidoProveedor', 'served', 'served', 'servido', $options);
            }
        };
    }
}
