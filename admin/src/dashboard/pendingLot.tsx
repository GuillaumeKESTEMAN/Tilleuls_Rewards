import * as React from 'react';
import {
    ListItemButton,
    ListItemSecondaryAction,
    ListItemAvatar,
    ListItemText,
    Avatar,
    Box,
} from '@mui/material';
import RedeemIcon from '@mui/icons-material/Redeem';
import {Link} from 'react-router-dom';
import {useReference} from 'react-admin';
import {LotRaRecord} from '../types/Lot';
import {MediaObjectRaRecord} from '../types/MediaObject';
// @ts-ignore
import {ENTRYPOINT} from "../config/components/entrypoint.ts";

interface Props {
    lot: LotRaRecord;
}

export const PendingLot = (props: Props) => {
    const {lot} = props;
    const {referenceRecord: image, isLoading} = useReference<MediaObjectRaRecord>({
        reference: 'media_objects',
        id: lot.image,
    });

    return (
        <ListItemButton component={Link} to={`/lots/%2Flots%2F${lot.originId}/show`}>
            <ListItemAvatar>
                {isLoading || !image ? (
                    <Avatar>
                        <RedeemIcon htmlColor={'#288690'}/>
                    </Avatar>
                ) : (
                    <Avatar
                        src={`${ENTRYPOINT}/image/${image.filePath}?size=32x32`}
                        sx={{
                            bgcolor: 'white',
                        }}
                    />
                )}
            </ListItemAvatar>
            <ListItemText
                primary={lot.name}
            />
            <ListItemSecondaryAction>
                <Box
                    component="span"
                    sx={{
                        marginRight: '1em',
                        color: 'text.primary',
                    }}
                >
                    quantité : {lot.quantity}
                </Box>
            </ListItemSecondaryAction>
        </ListItemButton>
    );
};
