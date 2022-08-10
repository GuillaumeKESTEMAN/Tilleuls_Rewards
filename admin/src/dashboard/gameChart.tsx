import * as React from 'react';
import { Card, CardHeader, CardContent } from '@mui/material';
import {
    ResponsiveContainer,
    AreaChart,
    Area,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
} from 'recharts';
import { useTranslate } from 'react-admin';
import { format, subDays, addDays } from 'date-fns';

import { Game } from '../types/Game';

const lastDay = new Date();
const lastMonthDays = Array.from({ length: 30 }, (_, i) => subDays(lastDay, i));
const aMonthAgo = subDays(new Date(), 30);

const dateFormatter = (date: number): string =>
    new Date(date).toLocaleDateString();

const aggregateGamesByDay = (games: Game[]): { [key: string]: number } =>
    games
        .reduce((acc, curr) => {
            const day = format(new Date(curr.playDate), 'yyyy-MM-dd');
            if (!acc[day]) {
                acc[day] = 0;
            }
            acc[day]++;
            return acc;
        }, {} as { [key: string]: number });

const getNbGamesPerDay = (games: Game[]): TotalByDay[] => {
    const daysWithGames = aggregateGamesByDay(games);
    return lastMonthDays.map(date => ({
        date: date.getTime(),
        total: daysWithGames[format(new Date(date), 'yyyy-MM-dd')] || 0,
    }));
};

const GameChart = (props: { games?: Game[] }) => {
    const { games } = props;
    const translate = useTranslate();
    if (!games) return (<CardHeader title={translate('pos.dashboard.games.no_games')} />);

    return (
        <Card>
            <CardHeader title={translate('pos.dashboard.games.month_history')} />
            <CardContent>
                <div style={{ width: '100%', height: 300 }}>
                    <ResponsiveContainer>
                        <AreaChart data={getNbGamesPerDay(games)}>
                            <defs>
                                <linearGradient
                                    id="colorUv"
                                    x1="0"
                                    y1="0"
                                    x2="0"
                                    y2="1"
                                >
                                    <stop
                                        offset="5%"
                                        stopColor="#288690"
                                        stopOpacity={0.8}
                                    />
                                    <stop
                                        offset="95%"
                                        stopColor="#288690"
                                        stopOpacity={0}
                                    />
                                </linearGradient>
                            </defs>
                            <XAxis
                                dataKey="date"
                                name="Date"
                                type="number"
                                scale="time"
                                domain={[
                                    addDays(aMonthAgo, 1).getTime(),
                                    new Date().getTime(),
                                ]}
                                tickFormatter={dateFormatter}
                            />
                            <YAxis dataKey="total" name="Game" />
                            <CartesianGrid strokeDasharray="3 3" />
                            <Tooltip
                                cursor={{ strokeDasharray: '3 3' }}
                                formatter={(value: any) =>
                                    new Intl.NumberFormat('fr').format(value)
                                }
                                labelFormatter={(label: any) =>
                                    dateFormatter(label)
                                }
                            />
                            <Area
                                type="monotone"
                                dataKey="total"
                                stroke="#288690"
                                strokeWidth={2}
                                fill="url(#colorUv)"
                            />
                        </AreaChart>
                    </ResponsiveContainer>
                </div>
            </CardContent>
        </Card>
    );
};

interface TotalByDay {
    date: number;
    total: number;
}

export default GameChart;
