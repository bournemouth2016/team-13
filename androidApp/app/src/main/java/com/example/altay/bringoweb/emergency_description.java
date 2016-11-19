package com.example.altay.bringoweb;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

import com.google.firebase.iid.FirebaseInstanceId;

import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;

public class emergency_description extends AppCompatActivity {

    private Button next;
    private Button cancel;
    private Boolean thread_running;
    private String token;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.emergency_description);

        next = (Button) findViewById(R.id.btn_life_threatening);
        cancel = (Button) findViewById(R.id.btn_false_alert);
        token = FirebaseInstanceId.getInstance().getToken();


        next.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i = new Intent(getApplicationContext(),life_threatening.class);
                startActivity(i);
            }
        });

        cancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Thread t = new Thread(new Runnable() {
                    @Override
                    public void run() {

                        while (thread_running) {

                            if (token != null) {

                                try {
                                    OkHttpClient client = new OkHttpClient();
                                    RequestBody body = new FormBody.Builder()
                                            .add("token", token)
                                            .add("type", "falsealarm")
                                            .build();

                                    Request request = new Request.Builder()
                                            .url("http://smartsail.esy.es/app/api.php")
                                            .post(body)
                                            .build();

                                    client.newCall(request).execute();
                                } catch (Exception e) {
                                    e.printStackTrace();
                                }

                                thread_running = false;

                                Intent i = new Intent(getApplicationContext(), EmergencyActivity.class);
                                startActivity(i);

                            } else {
                                System.out.println("- Token not retrieved -");
                            }
                            try {
                                Thread.sleep(1000);
                            } catch (InterruptedException e) {
                                e.printStackTrace();
                            }
                        }
                    }
                });
                t.start();
            }
        });
    }
}
