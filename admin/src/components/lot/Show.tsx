import {FunctionComponent} from "react";
import {Lot} from "../../types/Lot";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {ReferenceField} from "react-admin";

interface Props {
    lot: Lot;
}

// @ts-ignore
export const Show: FunctionComponent<Props> = ({lot}) => {
    return (
        <ShowGuesser {...lot}>
            <FieldGuesser source="name"/>
            <FieldGuesser source="quantity"/>
            <FieldGuesser source="message"/>
            <ReferenceField label="Image" source="image" reference="media_objects">
                <FieldGuesser source="name"/>
            </ReferenceField>
        </ShowGuesser>
    );
}
