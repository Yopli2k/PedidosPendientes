<?php
/**
 * This file is part of PedidosPendientes plugin for FacturaScripts.
 * FacturaScripts    Copyright (C) 2015-2024 Carlos Garcia Gomez <carlos@facturascripts.com>
 * PedidosPendientes Copyright (C) 2023-2024 Jose Antonio Cuello Principal <yopli2000@gmail.com>
 *
 * This program and its files are under the terms of the license specified in the LICENSE file.
 */
namespace FacturaScripts\Plugins\PedidosPendientes\Model\Join;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Dinamic\Model\Base\JoinModel;
use FacturaScripts\Dinamic\Model\LineaPedidoCliente;
use FacturaScripts\Dinamic\Lib\PedidosPendientes\PedidoPendiente;

/**
 * LineOrderPending detail model.
 *
 * @author Jose Antonio Cuello Principal <yopli2000@gmail.com>
 */
class LineOrderPending extends JoinModel
{

    /**
     * Constructor and class initializer.
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->setMasterModel(new LineaPedidoCliente());
    }

    /**
     * Load data for the indicated where.
     *
     * @param DataBaseWhere[] $where filters to apply to model records.
     * @param array $order fields to use in the sorting. For example ['code' => 'ASC']
     * @param int $offset
     * @param int $limit
     *
     * @return static[]
     */
    public function all(array $where, array $order = [], int $offset = 0, int $limit = 0): array
    {
        $where[] = new DataBaseWhere('cp.servido', PedidoPendiente::SERVED_COMPLETE, '<');
        $where[] = new DataBaseWhere('lp.servido - lp.cantidad', 0, '<');
        return parent::all($where, $order, $offset, $limit);
    }

    /**
     * Returns the number of records that meet the condition.
     *
     * @param DataBaseWhere[] $where filters to apply to records.
     * @return int
     */
    public function count(array $where = []): int
    {
        $where[] = new DataBaseWhere('cp.servido', PedidoPendiente::SERVED_COMPLETE, '<');
        $where[] = new DataBaseWhere('lp.servido - lp.cantidad', 0, '<');
        return parent::count($where);
    }

    /**
     * List of fields or columns to select clausule
     */
    protected function getFields(): array
    {
        return [
            'idlinea' => 'lp.idlinea',
            'idpedido' => 'lp.idpedido',
            'idproducto' => 'lp.idproducto',
            'referencia' => 'lp.referencia',
            'cantidad' => 'lp.cantidad',
            'servido' => 'lp.servido',
            'pendiente' => 'lp.cantidad - lp.servido',
            'pvpunitario' => 'COALESCE(lp.pvpunitario, 0.00)',
            'dtopor' => 'lp.dtopor',
            'dtopor2' => 'lp.dtopor2',
            'pvpsindto' => 'lp.pvpsindto',
            'pvptotal' => 'lp.pvptotal',

            'codigo' => 'cp.codigo',
            'codcliente' => 'cp.codcliente',
            'nombrecliente' => 'cp.nombrecliente',
            'finoferta' => 'cp.finoferta',
            'nick' => 'cp.nick',
            'idempresa' => 'cp.idempresa',

            'producto' => 'pr.referencia',
            'descripcion' => 'pr.descripcion',

            'empresa' => 'em.nombrecorto',
         ];
    }

    /**
     * List of tables related to from clausule
     */
    protected function getSQLFrom(): string
    {
        return 'lineaspedidoscli lp'
            . ' INNER JOIN pedidoscli cp ON cp.idpedido = lp.idpedido'
            . ' INNER JOIN productos pr ON pr.idproducto = lp.idproducto'
            . ' INNER JOIN empresas em ON em.idempresa = cp.idempresa';
    }

    /**
     * List of tables required for the execution of the view.
     */
    protected function getTables(): array
    {
        return [];
    }
}
