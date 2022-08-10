import {FunctionComponent} from "react";
import {Lot} from "../../types/Lot";
import {FieldGuesser, ListGuesser} from "@api-platform/admin";
import {ReferenceField} from "react-admin";

interface Props {
    lots: Lot[];
}

// @ts-ignore
export const List: FunctionComponent<Props> = ({lots}) => (
    <ListGuesser rowClick="show" {...lots}>
        <FieldGuesser source="name"/>
        <FieldGuesser source="quantity"/>
        <FieldGuesser source="message"/>
        <ReferenceField source="image" reference="media_objects" link="show">
            <FieldGuesser source="name"/>
        </ReferenceField>
    </ListGuesser>
);
