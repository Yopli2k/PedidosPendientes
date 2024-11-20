<?php
/**
 * This file is part of PedidosPendientes plugin for FacturaScripts.
 * FacturaScripts    Copyright (C) 2015-2024 Carlos Garcia Gomez <carlos@facturascripts.com>
 * PedidosPendientes Copyright (C) 2023-2024 Jose Antonio Cuello Principal <yopli2000@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Plugins\PedidosPendientes\Lib\PedidosPendientes;

/**
 * Description of PedidoPendiente
 *
 * @author Jose Antonio Cuello Principal <yopli2000@gmail.com>
 */
class PedidoPendiente
{
    public const SERVED_NONE = 0;
    public const SERVED_PARTIAL = 1;
    public const SERVED_COMPLETE = 100;
}
