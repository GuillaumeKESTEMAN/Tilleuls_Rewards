import * as React from 'react';
import {
    Card,
    CardHeader,
    List,
    Box,
    Button,
} from '@mui/material';
import { Link } from 'react-router-dom';
import {useTranslate} from 'react-admin';

import {LotRaRecord} from '../types/Lot';
// @ts-ignore
import {PendingLot} from './pendingLot.tsx';

interface Props {
    lots: LotRaRecord[];
}

const PendingLots = (props: Props) => {
    const { lots = [] } = props;
    const translate = useTranslate();

    return (
        <Card sx={{flex: 1}}>
            <CardHeader title={translate('pos.dashboard.lots.pending')}/>
            <List dense={true}>
                {lots.map(record => (
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
