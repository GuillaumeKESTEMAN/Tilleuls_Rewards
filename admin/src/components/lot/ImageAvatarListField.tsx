import * as React from 'react';
import {Avatar} from '@mui/material';
import {ReferenceField, useRecordContext} from 'react-admin';
import RedeemIcon from "@mui/icons-material/Redeem";
import {LotRaRecord} from "../../types/Lot";
// @ts-ignore
import AvatarField from "../avatar/AvatarField.tsx";

const ImageAvatarListField = () => {
    const record = useRecordContext<LotRaRecord>();

    if (!record) return null;
    return !record.image ? (
        <Avatar>
            <RedeemIcon htmlColor={'#288690'}/>
        </Avatar>
    ) : (
        <ReferenceField source="image" reference="media_objects" link="show">
            <AvatarField />
        </ReferenceField>
    )
};

export default ImageAvatarListField;
