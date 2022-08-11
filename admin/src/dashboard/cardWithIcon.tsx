import * as React from 'react';
import {FC, createElement} from 'react';
import {Card, Box, Typography, Divider} from '@mui/material';
import {ReactNode} from 'react';

interface Props {
    icon: FC<any>;
    title?: string;
    subtitle?: string | number;
    children?: ReactNode;
}

const CardWithIcon = (props: Props) => {
    const {icon, title, subtitle, children} = props;

    return (
        // @ts-ignore
        <Card
            sx={{
                minHeight: 52,
                display: 'flex',
                flexDirection: 'column',
                flex: '1',
                '& a': {
                    textDecoration: 'none',
                    color: 'inherit',
                },
            }}
        >
            <Box
                sx={{
                    overflow: 'inherit',
                    padding: '16px',
                    display: 'flex',
                    justifyContent: 'space-between',
                    alignItems: 'center',
                    '& .icon': {
                        color: theme =>
                            theme.palette.mode === 'dark'
                                ? 'inherit'
                                : '#288690',
                    },
                }}
            >
                <Box width="3em" className="icon">
                    {createElement(icon, {fontSize: 'large'})}
                </Box>
                <Box textAlign="right">
                    <Typography color="textSecondary">{title}</Typography>
                    <Typography variant="h5" component="h2">
                        {subtitle ?? ''}
                    </Typography>
                </Box>
            </Box>
            {children && <Divider/>}
            {children}
        </Card>
    );
};

export default CardWithIcon;
