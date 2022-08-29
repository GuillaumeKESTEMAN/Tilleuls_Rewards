import * as React from 'react';
import {Avatar} from '@mui/material';
import {useRecordContext} from 'react-admin';
// @ts-ignore
import {ENTRYPOINT} from "../../config/components/entrypoint.ts";
import {MediaObjectRaRecord} from "../../types/MediaObject";

const AvatarField = () => {
    const record = useRecordContext<MediaObjectRaRecord>();

    if (!record) return null;
    return (
        <Avatar
            src={`${ENTRYPOINT}/image/${record.filePath}?size=32x32`}
            sx={{
                bgcolor: 'white',
            }}
        />
    )
};

export default AvatarField;
