import * as React from 'react';
// @ts-ignore
import CardWithIcon from './cardWithIcon.tsx';
import {OverridableComponent} from "@mui/material/OverridableComponent";
import {SvgIconTypeMap} from "@mui/material/SvgIcon/SvgIcon";

interface Props {
    value?: number;
    icon?: OverridableComponent<SvgIconTypeMap>;
    title?: string;
}

const NbResource = (props: Props) => {
    const { value, icon, title } = props;
    return (
        <CardWithIcon
            icon={icon}
            title={title}
            subtitle={value}
        />
    );
};

export default NbResource;
