import * as React from 'react';
// @ts-ignore
import {
    EditButton,
    List,
    RaRecord,
    RecordContextProvider, ShowButton,
    useListContext
} from 'react-admin';
import inflection from 'inflection';
import {
    Grid,
    Card,
    CardMedia,
    CardContent,
    Typography,
} from '@mui/material';
import {MediaObjectRecord as MediaObject} from "../../types/MediaObject";
// @ts-ignore
import {ENTRYPOINT} from "../../config/entrypoint.ts";


// @ts-ignore
export const MediaList = () => (
    <List
        sort={{field: 'name', order: 'ASC'}}
        perPage={20}
        pagination={false}
        component="div"
    >
        <CategoryGrid/>
    </List>
);

const CategoryGrid = () => {
    const {data, isLoading} = useListContext<MediaObject>();
    if (isLoading) {
        return null;
    }

    return (
        <Grid container spacing={2} sx={{marginTop: '1em'}}>
            {data.map(record => (
                <RecordContextProvider key={record.id} value={record}>
                    <Grid
                        key={record.id}
                        xs={12}
                        sm={6}
                        md={4}
                        lg={3}
                        xl={2}
                        item
                    >
                        <Card sx={{textAlign: "center"}}>
                            <CardMedia
                                image={`${ENTRYPOINT}/image/${record.filePath}`}
                                sx={{height: 140}}
                            />
                            <CardContent sx={{paddingBottom: '0.5em'}}>
                                <Typography
                                    variant="h5"
                                    component="h2"
                                    align="center"
                                >
                                    {inflection.humanize(record.name)}
                                </Typography>
                            </CardContent>
                            <EditButton/>
                        </Card>
                    </Grid>
                </RecordContextProvider>
            ))}
        </Grid>
    );
}
