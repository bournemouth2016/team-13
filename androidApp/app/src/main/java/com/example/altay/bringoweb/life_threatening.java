package com.example.altay.bringoweb;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;


public class life_threatening extends AppCompatActivity {

    private Button next;
    private Button cancel;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.life_threatening_description);
        next = (Button) findViewById(R.id.btn_man_overboard);

        next.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i = new Intent(getApplicationContext(),waiting.class);
                startActivity(i);
            }
        });
    }
}
