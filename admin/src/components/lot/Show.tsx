import {FunctionComponent} from "react";
import {Lot} from "../../types/Lot";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {FunctionField, ReferenceField} from "react-admin";
// @ts-ignore
import {ENTRYPOINT} from "../../config/components/entrypoint.ts";

interface Props {
    lot: Lot;
}

export const Show: FunctionComponent<Props> = ({lot}) => {
    return (
        <ShowGuesser {...lot}>
            <FieldGuesser source="name"/>
            <FieldGuesser source="quantity"/>
            <FieldGuesser source="message" style={{whiteSpace: 'pre-line'}}/>
            <ReferenceField source="image" reference="media_objects" link="show">
                <FieldGuesser source="name"/><br/>
                <FunctionField
                    render={record => {return <img style={{ width:"100%", maxWidth: "300px", maxHeight: "300px"}} src={ENTRYPOINT + "/image/" + record.filePath} alt={record.name} />;}}
                />
            </ReferenceField>
        </ShowGuesser>
    );
}
