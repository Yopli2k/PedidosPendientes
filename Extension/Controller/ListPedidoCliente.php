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
use FacturaScripts\Core\DataSrc\Empresas;
use FacturaScripts\Core\Tools;
use FacturaScripts\Dinamic\Lib\PedidosPendientes\PedidoPendiente;

/**
 * Extension of ListPedido Controller
 *
 * @author José Antonio Cuello Principal <yopli2000@gmail.com>
 */
class ListPedidoCliente
{
    /**
     * Create the view to display.
     *   - Add servido filter
     */
    public function createViews(): Closure
    {
        return function () {
            $this->createViewsPendingLines();
            if (isset($this->views['ListPedidoCliente'])) {
                $options = [
                    ['code' => '', 'description' => '------'],
                    ['code' => PedidoPendiente::SERVED_NONE, 'description' => Tools::lang()->trans('pending')],
                    ['code' => PedidoPendiente::SERVED_PARTIAL, 'description' => Tools::lang()->trans('partial')],
                    ['code' => PedidoPendiente::SERVED_COMPLETE, 'description' => Tools::lang()->trans('completed')],
                ];
                $this->addFilterSelect('ListPedidoCliente', 'served', 'served', 'servido', $options);
            }
        };
    }

    public function createViewsPendingLines(): Closure
    {
        return function ($viewName = 'ListOrderPending') {
            $this->addView($viewName, 'Join\LineOrderPending', 'pending', 'far fa-list-alt');
            $this->addSearchFields($viewName, [
                'cp.codigo', 'cp.codcliente', 'cp.nombrecliente',
                'pr.referencia', 'pr.descripcion', 'lp.referencia',
            ]);

            $this->addOrderBy($viewName, ['pr.referencia, lp.referencia'], 'reference');
            $this->addOrderBy($viewName, ['cp.codcliente, pr.referencia, lp.referencia'], 'customer');
            $this->addOrderBy($viewName, ['cp.finoferta, pr.referencia, lp.referencia'], 'service-date');

            $this->addFilterPeriod($viewName, 'service', 'service-date', 'finoferta');
            $this->addFilterAutocomplete($viewName, 'customer', 'customer', 'cp.codcliente', 'Cliente', 'codcliente', 'nombre');
            $this->addFilterAutocomplete($viewName, 'product', 'product', 'pr.referencia', 'Producto', 'referencia', 'descripcion');
            $this->addFilterAutocomplete($viewName, 'reference', 'reference', 'lp.referencia', 'Variante', 'referencia', 'referencia');
            $this->addFilterSelect($viewName, 'company', 'company', 'cp.idempresa', Empresas::codeModel());
        };
    }
}
