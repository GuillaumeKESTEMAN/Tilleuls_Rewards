import {FunctionComponent} from "react";
import {Lot} from "../../types/Lot";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {ReferenceField} from "react-admin";

interface Props {
    lot: Lot;
}

// @ts-ignore
export const Show: FunctionComponent<Props> = ({lot}) => (
    <ShowGuesser {...lot}>
        <FieldGuesser source="name" addLabel={true}/>
        <FieldGuesser source="quantity" addLabel={true}/>
        <FieldGuesser source="message" addLabel={true}/>
        <ReferenceField label="Image" source="image" reference="media_objects">
            <FieldGuesser source="name" addLabel={true}/>
        </ReferenceField>
    </ShowGuesser>
);
