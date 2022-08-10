import * as React from 'react';
import {
    Card,
    CardHeader,
    List,
    Box,
    Button,
} from '@mui/material';
import { Link } from 'react-router-dom';
import {useGetList, useTranslate} from 'react-admin';

import {LotRaRecord} from '../types/Lot';
// @ts-ignore
import {PendingLot} from './pendingLot.tsx';
import {useMemo} from "react";

interface LotStats {
    pendingLots?: LotRaRecord[];
}

interface State {
    pendingLots?: LotRaRecord[];
}

const PendingLots = () => {
    const {data: lots} = useGetList<LotRaRecord>('lots', {
        sort: { field: 'quantity', order: 'ASC' },
        pagination: {page: 1, perPage: 10},
    });

    console.log(lots);

    // @ts-ignore
    const lotsAggregation = useMemo<State>(() => {
        if (!lots) return {};
        const aggregations = lots
            .filter(lot => lot.quantity > 0)
            .reduce(
                (stats: LotStats, lot) => {
                    stats.pendingLots.push(lot);
                    return stats;
                },
                {
                    pendingLots: [],
                }
            );
        return {
            pendingLots: aggregations.pendingLots,
        };
    }, [lots]);


    const translate = useTranslate();
    const {pendingLots} = lotsAggregation;

    return (
        <Card sx={{flex: 1}}>
            <CardHeader title={translate('pos.dashboard.lots.pending')}/>
            <List dense={true}>
                {pendingLots && pendingLots.map(record => (
                    <PendingLot key={record.id} lot={record}/>
                ))}
            </List>
            <Box flexGrow={1}>&nbsp;</Box>
            <Button
                sx={{borderRadius: 0}}
                component={Link}
                to="/lots"
                size="small"
                color="primary"
            >
                <Box p={1} sx={{color: 'primary.main'}}>
                    {translate('pos.dashboard.all_lots')}
                </Box>
            </Button>
        </Card>
    );
};

export default PendingLots;
